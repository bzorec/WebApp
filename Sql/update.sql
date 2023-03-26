ALTER TABLE vaja1.ads
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

ALTER TABLE vaja1.ads
    ADD COLUMN publish_date DATETIME DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE vaja1.ads
    ADD COLUMN price FLOAT NOT NULL;

ALTER TABLE users
    ADD COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user';

CREATE TABLE comments (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          ad_id INT NOT NULL,
                          user_id INT NOT NULL,
                          content TEXT NOT NULL,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          ip_address VARCHAR(45)
);