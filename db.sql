CREATE TABLE post (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    introduction TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    modify_at DATETIME,
    cover_image VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)