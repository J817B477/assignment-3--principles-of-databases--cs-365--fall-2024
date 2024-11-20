<?php
session_start();
// generates DB access credentials
const DBNAME = "student_passwords";
const DBHOST = "localhost";
const DBUSER = "passwords_user";

// generates encryption credentials
const KEY_STR = "JS04*KR_NTO{1112";
const INNIT_VECTOR = "LKJSE29448NDJSSK";
const AESCONFIG = 'aes-256-cbc';
