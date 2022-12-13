ALTER TABLE `projecttemplatepermissions` MODIFY COLUMN `projecttemplateuid` INTEGER NOT NULL DEFAULT -1,
 DROP PRIMARY KEY,
 ADD PRIMARY KEY(`userid`, `usertype`, `projecttemplateuid`);
 