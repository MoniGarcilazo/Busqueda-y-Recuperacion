<?php

$inset_posting_template = "INSERT INTO postings (id, id_doc, id_term, frequency) VALUES (:id, :id_doc, :id_term, :frequency)";
$insert_document_template = "INSERT INTO documents (id, name, creation_date, url, description) VALUES (:id, :name, :creation_date, :url, :description)";
$insert_vocabulary_template = "INSERT INTO vocabulary (id, terms, num_docs) VALUES (:id, :terms, :num_docs)";
$insert_position_template = "INSERT INTO position (id, id_post, id_term, id_doc, position) VALUES (:id, :id_post, :id_term, :id_doc, :position)";

$select_posting_template = "SELECT * FROM postings WHERE frequency > :frequency";
$select_document_template = "SELECT * FROM documents WHERE size > :size";
$select_vocabulary_template = "SELECT * FROM vocabulary WHERE num_docs > :num_docs";
$select_position_template = "SELECT * FROM position WHERE id_post = :id_post"; 
