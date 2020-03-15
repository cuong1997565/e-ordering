<?php
namespace App\Dashboard\ReadModel;

interface IDashboardDao {
    public function getAll();
    public function getCreditLimitExpired();
}
