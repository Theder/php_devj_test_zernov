SELECT user.id, user.login, user.password
FROM users user
INNER JOIN objects object
ON user.object_id = object.id