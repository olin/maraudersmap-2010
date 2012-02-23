SELECT placename,COUNT(*) FROM `point` WHERE placename LIKE "WH%" AND time < DATE('2007-12-31') GROUP BY placename;
