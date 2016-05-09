
drop table `#__cot_public`;

alter table `#__cot_admin` add `admin_validation` BOOLEAN NOT NULL default 0;
