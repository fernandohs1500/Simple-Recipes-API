CREATE SEQUENCE public.seq_id_rate
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 999999999
  START 4
  CACHE 1;
ALTER TABLE public.seq_id_rate
  OWNER TO hellofresh;

-- Sequence: public.seq_id_recipes

CREATE SEQUENCE public.seq_id_recipes
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 99999999999
  START 9
  CACHE 1;
ALTER TABLE public.seq_id_recipes
  OWNER TO hellofresh;

-- Sequence: public.seq_id_user

CREATE SEQUENCE public.seq_id_user
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE public.seq_id_user
  OWNER TO hellofresh;


CREATE TABLE public.rate
(
  id bigint NOT NULL DEFAULT nextval('seq_id_rate'::regclass),
  recipe_id bigint,
  rate smallint,
  CONSTRAINT "PK_rate" PRIMARY KEY (id),
  CONSTRAINT fk_recipe_id FOREIGN KEY (recipe_id)
      REFERENCES public.recipe (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.rate
  OWNER TO hellofresh;

-- Table: public.recipe

-- DROP TABLE public.recipe;

CREATE TABLE public.recipe
(
  id bigint NOT NULL DEFAULT nextval('seq_id_recipes'::regclass),
  name character varying(255),
  prep_time bigint,
  difficult smallint,
  bol_vegetarian boolean,
  CONSTRAINT pk_first PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.recipe
  OWNER TO hellofresh;

-- Table: public."user"

-- DROP TABLE public."user";

CREATE TABLE public."user"
(
  id bigint NOT NULL DEFAULT nextval('seq_id_user'::regclass),
  login character varying(50),
  passwd text,
  date_create date,
  nickname character varying(50),
  CONSTRAINT id PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public."user"
  OWNER TO hellofresh;
