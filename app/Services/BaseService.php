<?php
namespace Service;
use App\Services\Contracts\BaseServiceInterface;
use Illuminate\Support\Facades\Storage;
abstract class BaseService implements BaseServiceInterface {
    /**
     * Retrieve all data
     *
     * @param array $columns
     *
     * @return mixed
     */

    public function all($columns = ['*']) {
        return $this->repository->all();
    }


    /**
     * Retrieve find 1
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */

    public function find($id) {
        return $this->repository->find($id);
    }

    /**
     * Retrieve all data, paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*']) {
        return $this->repository->paginate($limit, $columns);
    }

     /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function show($id, $columns = ['*']) {
        return $this->repository->find($id, $columns);
    }

    /**
     * Save a new entity
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes) {
        return $this->repository->create($attributes);
    }

    /**
     * Update a entity by id
     *
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $attributes, $id) {
        return $this->repository->update($attributes, $id);
    }

    /**
     * Delete a entity by id
     *
     * @param $id
     *
     * @return int
     */
    public function delete($id) {
        return $this->repository->delete($id);
    }

    /**
     * Upload a file
     *
     * @param @file
     *
     * @return boolean
     */

    public function uploadFile($file, $fileName, $path = "documents")
    {
        if ($file) {
            return $file->storeAs($path,
                "{$fileName}"
            );
        }
    }

    /**
     * Detatch a file
     *
     * @param @file
     *
     * @return boolean
     */

    // public function detatchFile($file, array $attributes = [])
    // {

    // }

    // public static function readFile() {

    // }

    // public static function downloadFile() {

    // }
}
