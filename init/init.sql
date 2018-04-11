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
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/images/autumn.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/images/beach.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/images/corn.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/images/dock.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/images/ferriswheel.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/images/ice.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='gm'), 'uploads/images/LWP3.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/manhattan.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/moon.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/nyc.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/oneworld.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/sailboat.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/skeleton.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/sunset.jpg');
INSERT INTO photos(user_id, image_path) VALUES ((SELECT id FROM users WHERE username='janedoe'), 'uploads/images/water.jpg');

/* You should at least have 5 tags. */
INSERT INTO tags(tag) VALUES ('nature');
INSERT INTO tags(tag) VALUES ('portrait');
INSERT INTO tags(tag) VALUES ('city');
INSERT INTO tags(tag) VALUES ('design');
INSERT INTO tags(tag) VALUES ('lookup');

/* At least 3 tags must applied to at least 1 image. At least 8 images need to have a tag.
At least 3 images need to have multiple tags. */

/*LOOK UP*/
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='lookup'), (SELECT id FROM photos WHERE image_path='uploads/images/ice.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='lookup'), (SELECT id FROM photos WHERE image_path='uploads/images/manhattan.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='lookup'), (SELECT id FROM photos WHERE image_path='uploads/images/nyc.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='lookup'), (SELECT id FROM photos WHERE image_path='uploads/images/oneworld.jpg'));

/*NATURE*/
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='nature'), (SELECT id FROM photos WHERE image_path='uploads/images/corn.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='nature'), (SELECT id FROM photos WHERE image_path='uploads/images/dock.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='nature'), (SELECT id FROM photos WHERE image_path='uploads/images/ice.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='nature'), (SELECT id FROM photos WHERE image_path='uploads/images/moon.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='nature'), (SELECT id FROM photos WHERE image_path='uploads/images/sailboat.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='nature'), (SELECT id FROM photos WHERE image_path='uploads/images/sunset.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='nature'), (SELECT id FROM photos WHERE image_path='uploads/images/water.jpg'));

/*CITY*/
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='city'), (SELECT id FROM photos WHERE image_path='uploads/images/ferriswheel.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='city'), (SELECT id FROM photos WHERE image_path='uploads/images/LWP3.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='city'), (SELECT id FROM photos WHERE image_path='uploads/images/manhattan.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='city'), (SELECT id FROM photos WHERE image_path='uploads/images/nyc.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='city'), (SELECT id FROM photos WHERE image_path='uploads/images/oneworld.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='city'), (SELECT id FROM photos WHERE image_path='uploads/images/skeleton.jpg'));

/*PORTRAIT*/
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='portrait'), (SELECT id FROM photos WHERE image_path='uploads/images/autumn.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='portrait'), (SELECT id FROM photos WHERE image_path='uploads/images/beach.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='portrait'), (SELECT id FROM photos WHERE image_path='uploads/images/LWP3.jpg'));

/*DESIGN*/
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='design'), (SELECT id FROM photos WHERE image_path='uploads/images/ice.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='design'), (SELECT id FROM photos WHERE image_path='uploads/images/nyc.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='design'), (SELECT id FROM photos WHERE image_path='uploads/images/skeleton.jpg'));
INSERT INTO photo_tags(tag_id, photo_id) VALUES ((SELECT id FROM tags WHERE tag='design'), (SELECT id FROM photos WHERE image_path='uploads/images/manhattan.jpg'));
