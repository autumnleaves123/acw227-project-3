/* TODO: create tables */

/* FOREIGN KEY REFERENCES users(id) */

CREATE TABLE photos (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  user_id INTEGER NOT NULL,
  image_path TEXT NOT NULL,
  credit TEXT
);

CREATE TABLE users (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  username TEXT NOT NULL,
  password TEXT NOT NULL,
  session INTEGER UNIQUE
);

CREATE TABLE tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  tag TEXT NOT NULL UNIQUE
);

CREATE TABLE photo_tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  tag_id INTEGER NOT NULL,
  photo_id INTEGER NOT NULL
);

/* TODO: initial seed data */


/* You should have at least 2 user accounts. Specify the username and passwords
in your design-plan so we can test your web page.*/
INSERT INTO users(username, password) VALUES ('janedoe', '$2y$10$92IushmzxvE9gSiAEb8d2Op16RZSp.5Vtm4snVsyhP1oI8zrEnrOe'); /* Password is 'gobigred' */
INSERT INTO users(username, password) VALUES ('gm', '$2y$10$jFXqOXL7F.Q4rSBSNosGEus6cF2lOZ8vIVJoFpQCaGXOGggIfaqzq'); /* Password is 'liftthechorus' */

/* You should have at least 10 images. */
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/1.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/2.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/3.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/4.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/5.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/6.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/7.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/8.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/9.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/10.jpg');

/* You should at least have 5 tags. */
INSERT INTO tags(tag) VALUES ('nature');
INSERT INTO tags(tag) VALUES ('modern');
INSERT INTO tags(tag) VALUES ('city');
INSERT INTO tags(tag) VALUES ('design');
INSERT INTO tags(tag) VALUES ('lookup');

/* At least 3 tags must applied to at least 1 image. At least 8 images need to have a tag.
At least 3 images need to have multiple tags. */
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/1.jpg'), (SELECT id FROM tags WHERE tag='nature'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/1.jpg'), (SELECT id FROM tags WHERE tag='modern'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/1.jpg'), (SELECT id FROM tags WHERE tag='city'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/2.jpg'), (SELECT id FROM tags WHERE tag='nature'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/2.jpg'), (SELECT id FROM tags WHERE tag='modern'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/3.jpg'), (SELECT id FROM tags WHERE tag='city'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/3.jpg'), (SELECT id FROM tags WHERE tag='city'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/4.jpg'), (SELECT id FROM tags WHERE tag='city'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/5.jpg'), (SELECT id FROM tags WHERE tag='city'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/6.jpg'), (SELECT id FROM tags WHERE tag='city'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/7.jpg'), (SELECT id FROM tags WHERE tag='city'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM photos WHERE image_path='uploads/8.jpg'), (SELECT id FROM tags WHERE tag='city'));
