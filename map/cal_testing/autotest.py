from urllib2 import urlopen, URLError
from datetime import datetime, timedelta
from csv import reader
from retrobind import parse_map_location, JustShowCount
from adjacent_rooms import adjacent
from progressbar import *
from urllib import quote as urlquote
from pyx import *
import pickle

def none(l):
    if len(l) == 0:
        return True
    elif l[0] == False:
        return none(l[1:])
    else:
        return False

def uniquify(l, r=[]):
    '''Takes a list `l` and returns a list with no duplicates in it.
    We don't use set() because set() is unordered.'''
    if l == []:
        return r
    elif r != [] and l[0] in r:
        return uniquify(l[1:], r)
    else: # l[0] not in r
        return uniquify(l[1:], r + l[0:1])

def enum_list(l):
    return [[a,b] for a,b in enumerate(l)]

def prntitle(s):
    print '_'*80
    print s.upper()

def percent(a, b):
    return float(a) / float(b) * 100

def prntabular(*s):
    print '\t'.join(s)

class TestResult(object):
    def __init__(self, actual_room, map_room):
        self.actual_room = self.rmname(actual_room)
        self.map_room = self.rmname(map_room)
        self.correct_room = self.map_room == self.actual_room
        self.adjacent_room = adjacent(self.map_room, self.actual_room)
        try:
            self.correct_floor = self.map_room[2] == self.actual_room[2]
        except IndexError:
            self.correct_floor = False
            print 'Error 1 in TestResult.__init__():'
            print '  ' + map_room
            print '  ' + actual_room
        if self.correct_room:
            self.status_string = '    '
        elif self.adjacent_room:
            self.status_string = '   a'
        elif self.correct_floor:
            self.status_string = '  x '
        else:
            self.status_string = ' F  '

    def rmname(self, rm):
        '''Takes room number (eg 213) and returns a string room
        name (eg "AC213").  If passed a string, do nothing.'''
        return rm if type(rm) == type('str') else 'AC' + str(rm)

    def __repr__(self):
        return '   ' + self.status_string +'\t'+ self.actual_room \
            +'\t'+ self.map_room

ground_truth = [
    TestResult(213,213),
    TestResult(213,213),
    TestResult(218,218),
    TestResult(226,218),
    TestResult(226,126),
    TestResult(226,126),
    TestResult(228,128),
    TestResult(209,206),
    TestResult(209,206),
    TestResult(206,206),
    TestResult(206,206),
    TestResult(204,204),
    TestResult(204,204),
    TestResult(304,304),
    TestResult(304,304),
    TestResult(306,306),
    TestResult(306,204),
    TestResult(306,306),
    TestResult(309,309),
    TestResult(309,309),
    TestResult(313,309),
    TestResult(313,313),
    TestResult(313,313),
    TestResult(318,318),
    TestResult(318,318),
    TestResult(326,326),
    TestResult(326,326),
    TestResult(328,328),
    TestResult(328,328),
    TestResult(428,428),
    TestResult(428,428),
    TestResult(426,428),
    TestResult(426,428),
    TestResult(429,428),
    TestResult(429,428),
    TestResult(417,417),
    TestResult(417,417),
    TestResult(413,417),
    TestResult(413,309),
    TestResult(413,309),
    TestResult(409,409),
    TestResult(409,409),
    TestResult(406,409),
    TestResult(406,409),
    TestResult(404,409),
    TestResult(404,409),
    TestResult(102,102),
    TestResult(102,102),
    TestResult(109,109),
    TestResult(109,109),
    TestResult(113,109),
    TestResult(113,109),
    TestResult(113,109),
    TestResult(116,109),
    TestResult(116,109),
    TestResult(126,126),
    TestResult(126,126),
    TestResult(128,126),
    TestResult(128,126),
    TestResult(128,128)]

def mysqldate(datetime):
    '''Takes three integers and produces a datetime at midnight when that
    date began.'''
    return datetime.strftime('%Y-%m-%d %H:%M:%S')

def filedaystamp(datetime):
    '''formats a datetime to be used in a filename. returns a string.'''
    return datetime.strftime('%Y-%m-%d')

def query_map(url, mintime=datetime(2007,1,1,0,0,0),
                   maxtime=datetime(9999,1,1,0,0,0),
              local=True):
    try:
        u = urlopen(url + '&mintime=' + urlquote(mysqldate(mintime)) + \
                          '&maxtime=' + urlquote(mysqldate(maxtime)))
        c = u.read().strip()
        u.close()
        if c[0:7] == 'success':
            r = parse_map_location(c[8:c.find('|')])
            if r == '08':
                print u.url
            return r
        else:
            return '[localizer failed]'
    except URLError:
        print "Failed to fetch webpage.  Can you see the ACL on your network?"
    
def run_test(testdata_filename, mintime=datetime(2007,1,1,0,0,0),
                                maxtime=datetime(9999,1,1,0,0,0)):
    return [TestResult(actual_room, query_map(url, mintime, maxtime)) \
                for url, actual_room in reader(open(testdata_filename))]

def print_test_results(test_results):
    print "Success\tActual\tLocalized"
    for tr in test_results:
        print tr
        
    correct_room = [tr for tr in test_results if tr.correct_room]
    correct_floor = [tr for tr in test_results if tr.correct_floor]

    print "%i%% of localizations were in the correct room." % \
        correct_room_percent(test_results)

    print "%i%% of localizations were correct, or adjacent to the correct room." % \
        adjacent_room_percent(test_results)

    print "%i%% of localizations were on the correct floor." % \
        (float(len(correct_floor)) / len(test_results) * 100)

    print "The following rooms failed at least once:", \
        uniquify([tr.actual_room for tr in test_results if not tr.correct_room])

    print "The following rooms failed every time:", \
        failed_every_time(test_results)

def correct_room_percent(test_results):
    correct_room = [tr for tr in test_results if tr.correct_room]
    return percent(len(correct_room), len(test_results))

def adjacent_room_percent(test_results):
    adjacent_room = [tr for tr in test_results if tr.adjacent_room]
    return correct_room_percent(test_results) + \
        percent(len(adjacent_room), len(test_results))

def correct_floor_percent(test_results):
    correct_floor = [tr for tr in test_results if tr.correct_floor]
    return percent(len(correct_floor), len(test_results))

def tests_in_room(test_results, rm):
    return [tr for tr in test_results if tr.actual_room == rm]

def failed_every_time(test_results):
    tested_rooms = uniquify([tr.actual_room for tr in test_results])
    return [rm for rm in tested_rooms \
                if none([tr.correct_room \
                             for tr in tests_in_room(test_results,rm)])]

def print_discrepancy(name_a, results_a, name_b, results_b):
    '''used to test autotester by comparing it to manual test'''
    discrepancy_count = 0
    print 'actual\t%s\t%s' % (name_a, name_b)
    assert len(results_a) == len(results_b)
    for a, b in zip(results_a, results_b):
        assert a.actual_room == b.actual_room
        if a.map_room != b.map_room:
            prntabular(a.actual_room, a.map_room, b.map_room)
            discrepancy_count += 1
    print "%i%% of tests did not match." % percent(discrepancy_count, len(results_a))
    print '%s: %i%% correct room' % (name_a, correct_room_percent(results_a))
    print '%s: %i%% correct or adjacent room' % \
        (name_a, adjacent_room_percent(results_a))
    print '%s: %i%% correct floor' % (name_a, correct_floor_percent(results_a)) 
    print '%s: %i%% correct room' % (name_b, correct_room_percent(results_b))
    print '%s: %i%% correct room or adjacent room' % \
        (name_b, adjacent_room_percent(results_b))
    print '%s: %i%% correct floor' % (name_b, correct_floor_percent(results_b))

def plot_to_eps(filename, xtitle, ytitle, *datasets):
    g = graph.graphxy(width=8,
                      x=graph.axis.linear(title=xtitle),
                      y=graph.axis.linear(title=ytitle))
    g.plot([graph.data.list(enum_list(dataset), x=1, y=2) \
                for dataset in datasets],
           [graph.style.line()])
    g.writeEPSfile(filename)
    
def print_report():
    '''shortcut function.  runs tests and prints report.'''
    print ('#'*80+'\n')*6
    prntitle('test results reported by andy barry')
    print_test_results(ground_truth)
    
    prntitle('automated test results')
    autoresults = run_test('acTest.csv')
    print_test_results(autoresults)
    
    prntitle('discrepancy between andy barry\'s test and automated test')
    print_discrepancy('andy', ground_truth, 'auto', autoresults)

    live_results = run_test('acTest_live.csv')
    prntitle('test of live, running Map')
    print_test_results(live_results)

    prntitle('discrepancy between live map and calendar map tests')
    print_discrepancy('live', live_results, 'cal', autoresults)

def day_range(start, end):
    current = start
    r = []
    while current <= end:
        r.append(current)
        current = current + timedelta(days=1)
    return r

def run_ranged_test(pbarlabel, startday, inputfilename):
    '''Tests the Map's historic accuracy from startday until today.  Writes
    output to a pkl at outputfilename.'''
    endday = datetime.now()
    days = day_range(startday, endday)
    pbar = ProgressBar(widgets=[pbarlabel, JustShowCount(), ' ', Bar()],
                       maxval=len(days)).start()
    count = 0
    
    results = []
    for maxday in days:
        results.append(run_test(inputfilename, startday, maxday))
        count += 1
        pbar.update(count)

    pbar.finish()
    return results

def pkl_ranged_test(pbarlabel, startday, outputfilename, inputfilename):
    pkl_file = open(outputfilename, 'wb')
    pickle.dump(run_ranged_test(pbarlabel, startday, inputfilename), pkl_file)
    pkl_file.close()

def run_ranged_test_test():
    pkl_ranged_test('Running ranged test test on live server ',
                    datetime(2008,3,7,0,0,0),
                    'ranged_test_test.pkl', 'acTest_localhost.csv')
    generate_csvs_from_pkl('test')

def run_ranged_tests():
    '''Shortcut function.  Runs historic tests on live and cal Maps.'''
    pkl_ranged_test('Running ranged tests on live server',
                    datetime(2007,12,18,0,0,0),
                    'ranged_test_live.pkl', 'acTest_localhost.csv')
    pkl_ranged_test('Running ranged tests on calendar server',
                    datetime(2008,04,07,0,0,0),
                    'ranged_test_cal.pkl', 'acTest.csv')

def generate_csvs_from_pkl(name):
    filename = 'ranged_test_' + name
    pkl_file = open(filename+'.pkl', 'rb')
    test_results_set = pickle.load(pkl_file)
    pkl_file.close()

    m_file = open(filename+'.m', 'w')
    m_file.write('\ncorrect_room_percent = ' + [correct_room_percent(ranged_test) for ranged_test in test_results_set].__repr__())

    m_file.write('\nadjacent_room_percent = ' + [adjacent_room_percent(ranged_test) for ranged_test in test_results_set].__repr__())

    m_file.write('\ncorrect_floor_percent = ' + [correct_floor_percent(ranged_test) for ranged_test in test_results_set].__repr__())

    m_file.close()

    print('generated %s from %s' % (filename+'.pkl', filename+'.m'))

    
if __name__ == '__main__':
    print 'running tests and generating *.m files from test_ranged_live.pkl and test_ranged_cal.pkl'
    run_ranged_test_test()
    # generate_csvs_from_pkl('live')
    # generate_csvs_from_pkl('cal')
