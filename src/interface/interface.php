<?php

interface IMain
{
    function Main();
}

interface IDatabase
{
    function __construct();
    function createDatabase();
    function getDB();
    function closeConnection();
    function hashPassword(string $password);
    function messageResponse();
}

interface IBookCheck
{
    function __construct();
    function checkBookId();
    function checkBookUpdate();
    function checkBookAll(bool $check);
    function checkBook();
    function checkGanre();
    function getName();
    function getCount();
    function getCreator();
    function getDateCreated();
    function getId();
}

interface IUserCheck
{
    function __construct();
    function checkParams();
    function userCheckAllParameters(bool $check);
    function userUpdate();
    function getThisMaxUser();
    function getThisId();
    function getThisLimit();
    function getThisOffset();
    function getThisName();
    function getThisSurName();
    function getThisPasswordFirst();
    function getThisPasswordLast();
}

interface IEnv
{
    function getEnv();
}

interface IHandler
{
    function __construct();
    function handleMethod();
}