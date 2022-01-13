CREATE FUNCTION fnSplit(
	str VARCHAR(255) ,
	delim VARCHAR(12) ,
	pos INT
) RETURNS VARCHAR(255) CHARSET utf8 RETURN REPLACE(
	SUBSTRING(
		SUBSTRING_INDEX(str , delim , pos) ,
		CHAR_LENGTH(
			SUBSTRING_INDEX(str , delim , pos - 1)
		) + 1
	) ,
	delim ,
	''
);