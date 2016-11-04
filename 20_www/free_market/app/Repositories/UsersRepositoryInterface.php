<?php
namespace App\Repositories;

/**
 * Interface UsersRepositoryInterface
 */
interface UsersRepositoryInterface
{

    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function validate(array $params);

    /**
     * @return mixed
     */
    public function getErrors();

    /**
     * @param array $user
     *
     * @return mixed
     */
    public function insertGetId(array $user);
    
    /**
     * @param array $params
     *
     * @return mixed
     */
    public function save(array $params);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * @return mixed
     */
    public function count();

    /**
     * @param int $page
     * @param int $limit
     *
     * @return mixed
     */
    public function byPage($page = 1, $limit = 20);
}