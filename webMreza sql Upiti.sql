SET SQL_SAFE_UPDATES = 0;

select count(*) 
from posts p 
inner join users u on p.post_creator = u.id 
where u.username = 'admin';

SELECT * FROM users WHERE username = 'admin';

select * from users;

Update users
Set username = 'neca',email='nekimail@gmail.com',bio='neka biographija'
where username='necaiduca';

SELECT * FROM users WHERE username LIKE '%a%';

