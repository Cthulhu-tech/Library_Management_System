<?php

interface IMain
{
    function Main();
}

interface IDatabase
{
    function __construct();
    function createDatabase(): void;
    function getDB(): PDO;
    function closeConnection(): void;
    function hashPassword(string $password): string;
    function messageResponse(): void;
}

interface IBookCheck
{
    function __construct();
    function checkBookId(): bool;
    function checkBookUpdate(): bool;
    function checkBookAll(bool $check): bool;
    function checkBook(): bool;
    function checkGanre(): bool;
    function getName(): string;
    function getCount(): int;
    function getCreator(): string;
    function getDateCreated(): int;
    function getId(): int;
}

interface IUserCheck
{
    function __construct();
    function checkParams(): bool;
    function userCheckAllParameters(bool $check): bool;
    function userUpdate(): bool;
    function getThisMaxUser(): int;
    function getThisId(): int;
    function getThisLimit(): int;
    function getThisOffset(): int;
    function getThisName(): string;
    function getThisSurName(): string;
    function getThisPasswordFirst(): int;
    function getThisPasswordLast(): int;
}

interface IEnv
{
    function getEnv(): void;
}

interface IHandler
{
    function __construct();
    function handleMethod(): void;
}