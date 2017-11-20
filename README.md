# TODO app

This is a basic to-do list implemented with an N-tier architecture.

* MySQL database
* PHP backend API
* Javascript frontend ([Vue JS][vue-todo])

## Create Database

Provision a database on the server using [`CREATE DATABASE` syntax][create-database].

```sql
CREATE DATABASE todoapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
```

See also: [choosing a collation][choosing-collation].

### Define Schema

Execute the [data definition language][ddl] for the application.

    mysql -u root -p todoapp < sql/ddl.sql

## Create Users

Provision a user account on the server using [`CREATE USER` syntax][create-user].

```sql
CREATE USER 'jeff'@'localhost' IDENTIFIED BY 'ty[VN26Zd~FmSEmPQX{PY^3d^01~qUS6';
```

### Grant Permissions

Authorize access using [`GRANT` syntax][grant-syntax]
following the [principle of least privilege][least-privilege-principle].

```sql
GRANT SELECT ON todoapp.view_todos TO 'jeff'@'localhost';
GRANT SELECT (id) ON todoapp.todos TO 'jeff'@'localhost';
GRANT INSERT ON todoapp.todos TO 'jeff'@'localhost';
GRANT UPDATE ON todoapp.todos TO 'jeff'@'localhost';
GRANT DELETE ON todoapp.todos TO 'jeff'@'localhost';
```

[vue-todo]:https://vuejs.org/v2/examples/todomvc.html
[create-database]:https://dev.mysql.com/doc/refman/5.7/en/create-database.html
[choosing-collation]:https://stackoverflow.com/a/38363567/4233593
[ddl]:./sql/ddl.sql
[create-user]:https://dev.mysql.com/doc/refman/5.7/en/create-user.html
[grant-syntax]:https://dev.mysql.com/doc/refman/5.7/en/grant.html
[least-privilege-principle]:https://en.wikipedia.org/wiki/Principle_of_least_privilege
