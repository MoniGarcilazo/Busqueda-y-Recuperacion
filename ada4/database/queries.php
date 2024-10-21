<?php

$insert_posting_template = "INSERT INTO postings (id_doc, id_term, frequency) VALUES (:id_doc, :id_term, :frequency)";
$insert_document_template = "INSERT INTO documents (name, creation_date, url, description) 
                    VALUES (:name, :creation_date, :url, :description)";
$insert_vocabulary_template_not_exist = "INSERT INTO vocabulary (terms, num_docs) VALUES (:terms, 1)";
$insert_vocabulary_template_exist = "UPDATE vocabulary SET num_docs = num_docs + 1 WHERE id = :id";
$insert_position_template = "INSERT INTO position (id, id_post, id_term, id_doc, position) VALUES (:id, :id_post, :id_term, :id_doc, :position)";

$select_posting_template = "SELECT * FROM postings WHERE frequency > :frequency";
$select_document_template = "SELECT * FROM documents WHERE size > :size";
$select_vocabulary_template = "SELECT * FROM vocabulary WHERE num_docs > :num_docs";
$select_position_template = "SELECT * FROM position WHERE id_post = :id_post"; 
