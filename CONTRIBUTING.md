# CONTRIBUTING
Everybody is welcome to contribute to this free software project.
There aren't many rules attached in order to hand in a contribution.
Just follow these guidelines and everything should be fine.

## Code Style Guidelines
- function/method/variable names use `lower_case`
- class names use `PascalCase`
- constant names use `UPPER_CASE`
- always annotate types in function declarations, unless the parameter is expected to receive values of varying types
- non-public data members of class should be prefixed with an underscore (`_`)

## Feature Cost
Everything comes at a cost.
So do new features.
That cost does not have to be bad, but it has to be kept in mind.

Some features focus on solving a problem (or at least a big pain) in development, whilst other features are just introduced to provide more comfort.
The latter one of these two options is the one to be avoided.
Comfort itself is a very nice thing, but often times it does not just come at the cost of performance, but also at the cost of power.

One good example for such a comfort feature is a query builder.
It really does help at querying simple data a lot easier and maybe even more descriptive than it would be with a regular SQL query.
However, performing really complex queries with a lot of cross-relation/table references, is already hard in SQL and possibly harder in a query builder.
Another benefit is, that most query builders enable you to use different database systems, so you do not depend on a certain dialect of SQL.
That point also doesn't matter, as you are not really likely to switch your DMBS more than once.
So, you can just write raw SQL and you will just be fine.

One example for the first category of features, which is also database related, would be a function, which inserts a tuple in a relation and retrieves the generated primary key value of that newly inserted tuple.
That way, the developer (and therefore the software/application) doesn't have to query the inserted tuple to get the new primary key value manually, but it can be used immediatly.

So, features which solve a re-occurring problem or complexity are very welcome. But, if it just add a little comfort, at the cost of performance and/or power/control, I wouldn't be too sure if it get's accepted.