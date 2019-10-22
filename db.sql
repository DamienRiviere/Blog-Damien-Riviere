CREATE TABLE post (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    introduction TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    modify_at DATETIME,
    cover_image VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_user_post
        FOREIGN KEY (user_id)
        REFERENCES user (id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
)

CREATE TABLE category (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    style VARCHAR(255),
    PRIMARY KEY (id)
)

CREATE TABLE post_category (
    post_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, category_id),
    CONSTRAINT fk_post
        FOREIGN KEY (post_id) 
        REFERENCES post (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
    CONSTRAINT fk_category
        FOREIGN KEY (category_id) 
        REFERENCES category (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)

CREATE TABLE user (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    picture VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_role
        FOREIGN KEY (role_id)
        REFERENCES  role (id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
)

CREATE TABLE role (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)

CREATE TABLE comment (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    post_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    modify_at DATETIME,
    PRIMARY KEY (id, post_id, user_id),
    CONSTRAINT fk_post_comment
        FOREIGN KEY (post_id)
        REFERENCES post (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
    CONSTRAINT fk_user
        FOREIGN KEY (user_id)
        REFERENCES user (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)