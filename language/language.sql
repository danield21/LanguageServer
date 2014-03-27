CREATE TABLE accounts (
  account_id int(11) NOT NULL,
  username varchar(30) NOT NULL,
  password varchar(50) NOT NULL,
  first_name varchar(45) DEFAULT NULL,
  last_name varchar(45) DEFAULT NULL,
  admin tinyint(1) DEFAULT '0',
  PRIMARY KEY (account_id),
  UNIQUE KEY username_UNIQUE (username)
);

CREATE TABLE category (
  category_id int(11) NOT NULL AUTO_INCREMENT,
  category varchar(50) NOT NULL,
  description varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (category_id),
  UNIQUE KEY category_UNIQUE (category),
  UNIQUE KEY category (category)
) TYPE=InnoDB  AUTO_INCREMENT=1;

CREATE TABLE is_in (
  word_id int(11) NOT NULL,
  category_id int(11) NOT NULL,
  KEY category_id_idx (category_id),
  KEY word_id_idx (word_id)
) TYPE=InnoDB;

CREATE TABLE is_subcategory_of (
  subcategory_id int(11) DEFAULT NULL,
  parentcategory_id int(11) DEFAULT NULL,
  KEY subcategory_id (subcategory_id),
  KEY parentcategory_id (parentcategory_id)
) TYPE=InnoDB;

CREATE TABLE language (
  language_id int(11) NOT NULL AUTO_INCREMENT,
  language varchar(45) NOT NULL,
  PRIMARY KEY (language_id),
  UNIQUE KEY language_UNIQUE (language)
) TYPE=InnoDB  AUTO_INCREMENT=4 ;

CREATE TABLE word (
  word_id int(11) NOT NULL AUTO_INCREMENT,
  word varchar(50) NOT NULL,
  sound_type char(4) DEFAULT NULL,
  picture_type char(4) DEFAULT NULL,
  language_id int(11) DEFAULT NULL,
  PRIMARY KEY (word_id),
  UNIQUE KEY word_UNIQUE (word),
  KEY language_id (language_id)
) TYPE=InnoDB  AUTO_INCREMENT=1;

ALTER TABLE is_in
  ADD CONSTRAINT category_id FOREIGN KEY (category_id) REFERENCES category (category_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT word_id FOREIGN KEY (word_id) REFERENCES word (word_id) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table is_subcategory_of
--
ALTER TABLE is_subcategory_of
  ADD CONSTRAINT is_subcategory_of_ibfk_1 FOREIGN KEY (subcategory_id) REFERENCES category (category_id),
  ADD CONSTRAINT is_subcategory_of_ibfk_2 FOREIGN KEY (parentcategory_id) REFERENCES category (category_id);

--
-- Constraints for table word
--
ALTER TABLE word
  ADD CONSTRAINT word_ibfk_1 FOREIGN KEY (language_id) REFERENCES language (language_id);
