ALTER TABLE `#__extrawatch` MODIFY `city` VARCHAR( 255 ) DEFAULT NULL COLLATE utf8_general_ci; 
ALTER TABLE `#__extrawatch_history` MODIFY `city` VARCHAR( 255 ) DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_goals` MODIFY `clicked_element_xpath_condition` VARCHAR( 2048 ) DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_goals` MODIFY `send_email` varchar(3) DEFAULT NULL COLLATE utf8_general_ci; 
ALTER TABLE `#__extrawatch_dm_paths` MODIFY `allowedReferrer` varchar(255) DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_ip2c_cache` MODIFY ip varchar(255) not null COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_ip2c_cache` MODIFY countryCode varchar(255) not null COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_user_log` MODIFY countryCode varchar(255) DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_user_log` MODIFY ip varchar(255) DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_dm_referrer` MODIFY `referrer` varchar(255) DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_dm_counter` MODIFY `ip` varchar(255) DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_goals` MODIFY `clicked_element_xpath_condition` VARCHAR( 255 ) NULL DEFAULT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_dm_paths` MODIFY `dname` varchar(255) NOT NULL COLLATE utf8_general_ci;
ALTER TABLE `#__extrawatch_dm_extension` MODIFY `extname` varchar(25) NOT NULL COLLATE utf8_general_ci;

