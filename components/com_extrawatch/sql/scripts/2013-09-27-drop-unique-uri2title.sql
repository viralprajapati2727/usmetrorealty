ALTER TABLE #__extrawatch_uri2title DROP INDEX uri;
ALTER TABLE #__extrawatch_uri2title DROP INDEX uri_2;
ALTER TABLE #__extrawatch_uri2title ADD UNIQUE INDEX(uri, title);