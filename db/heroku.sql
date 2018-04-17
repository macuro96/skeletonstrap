DROP TABLE IF EXISTS auth_assignment CASCADE;
DROP TABLE IF EXISTS auth_item CASCADE;
DROP TABLE IF EXISTS auth_item_child CASCADE;
DROP TABLE IF EXISTS auth_rule CASCADE;

-- Table: auth_rule

-- DROP TABLE auth_rule;

CREATE TABLE auth_rule
(
  name character varying(64) NOT NULL,
  data bytea,
  created_at integer,
  updated_at integer,
  CONSTRAINT auth_rule_pkey PRIMARY KEY (name)
)
WITH (
  OIDS=FALSE
);


  -- Table: auth_item

  -- DROP TABLE auth_item;

  CREATE TABLE auth_item
  (
    name character varying(64) NOT NULL,
    type smallint NOT NULL,
    description text,
    rule_name character varying(64),
    data bytea,
    created_at integer,
    updated_at integer,
    CONSTRAINT auth_item_pkey PRIMARY KEY (name),
    CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name)
        REFERENCES auth_rule (name) MATCH SIMPLE
        ON UPDATE CASCADE ON DELETE SET NULL
  )
  WITH (
    OIDS=FALSE
  );

  -- Table: auth_assignment

DROP TABLE auth_assignment;

  CREATE TABLE auth_assignment
  (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer,
    CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id),
    CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name)
        REFERENCES auth_item (name) MATCH SIMPLE
        ON UPDATE CASCADE ON DELETE CASCADE
  )
  WITH (
    OIDS=FALSE
  );

  -- Index: auth_assignment_user_id_idx

DROP INDEX auth_assignment_user_id_idx;

  CREATE INDEX auth_assignment_user_id_idx
    ON auth_assignment
    USING btree
    (user_id COLLATE pg_catalog."default");

  -- Index: "idx-auth_item-type"

DROP INDEX "idx-auth_item-type";

  CREATE INDEX "idx-auth_item-type"
    ON auth_item
    USING btree
    (type);

    -- Table: auth_item_child

    -- DROP TABLE auth_item_child;

    CREATE TABLE auth_item_child
    (
      parent character varying(64) NOT NULL,
      child character varying(64) NOT NULL,
      CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child),
      CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child)
          REFERENCES auth_item (name) MATCH SIMPLE
          ON UPDATE CASCADE ON DELETE CASCADE,
      CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent)
          REFERENCES auth_item (name) MATCH SIMPLE
          ON UPDATE CASCADE ON DELETE CASCADE
    )
    WITH (
      OIDS=FALSE
    );
