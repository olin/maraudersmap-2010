SELECT placename,COUNT(*) FROM `point` WHERE placename LIKE "WH%" AND time < DATE('2008-04-27') GROUP BY placename;
