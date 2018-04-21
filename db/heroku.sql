DROP TABLE IF EXISTS auth_assignment CASCADE;
DROP TABLE IF EXISTS auth_item CASCADE;
DROP TABLE IF EXISTS auth_item_child CASCADE;
DROP TABLE IF EXISTS auth_rule CASCADE;
DROP INDEX IF EXISTS auth_assignment_user_id_idx CASCADE;
DROP INDEX IF EXISTS "idx-auth_item-type" CASCADE;

CREATE TABLE auth_rule
(
      name       CHARACTER VARYING(64)  NOT NULL
    , data       BYTEA
    , created_at INTEGER
    , updated_at INTEGER
    , CONSTRAINT auth_rule_pkey         PRIMARY KEY (name)
)
WITH (
      OIDS=FALSE
);

CREATE TABLE auth_item
(
      name        CHARACTER VARYING(64)  NOT NULL
    , type        SMALLINT               NOT NULL
    , description TEXT
    , rule_name   CHARACTER VARYING(64)
    , data        BYTEA
    , created_at  INTEGER
    , updated_at  INTEGER
    , CONSTRAINT auth_item_pkey PRIMARY KEY (name)
    , CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name)
                                          REFERENCES auth_rule (name) MATCH SIMPLE
                                          ON UPDATE CASCADE ON DELETE SET NULL

)
WITH (
      OIDS=FALSE
);

CREATE TABLE auth_assignment
(
      item_name CHARACTER VARYING(64)  NOT NULL
    , user_id   CHARACTER VARYING(64)  NOT NULL
    , created_at INTEGER
    , CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id)
    , CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name)
                                                REFERENCES auth_item (name) MATCH SIMPLE
                                                ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
      OIDS=FALSE
);

CREATE INDEX auth_assignment_user_id_idx
ON auth_assignment
USING btree
(user_id COLLATE pg_catalog."default");

CREATE INDEX "idx-auth_item-type"
ON auth_item
USING btree
(type);

CREATE TABLE auth_item_child
(
      parent CHARACTER VARYING(64)  NOT NULL
    , child  CHARACTER VARYING(64)  NOT NULL
    , CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child)
    , CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child)
                                            REFERENCES auth_item (name) MATCH SIMPLE
                                            ON UPDATE CASCADE ON DELETE CASCADE
    , CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent)
                                             REFERENCES auth_item (name) MATCH SIMPLE
                                             ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
