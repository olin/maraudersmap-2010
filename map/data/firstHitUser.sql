SELECT user, UNIX_TIMESTAMP(MIN(date)) FROM data WHERE page="/map/update.php" GROUP BY user;
