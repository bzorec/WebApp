ALTER TABLE ads
    ADD COLUMN category_id INT UNSIGNED NOT NULL,
ADD CONSTRAINT fk_category_id
FOREIGN KEY (category_id) REFERENCES categories(id);

CREATE TABLE categories
(
    id   INT         NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO categories (name)
VALUES ('Electronics'),
       ('Clothing'),
       ('Home and Garden'),
       ('Sports and Outdoors'),
       ('Books'),
       ('Toys and Games');

ALTER TABLE ads
    ADD COLUMN publish_date DATETIME DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE ads ADD COLUMN price FLOAT NOT NULL;
