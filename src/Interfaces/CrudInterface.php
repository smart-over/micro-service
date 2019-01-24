<?php

namespace SmartOver\MicroService\Interfaces;

use Illuminate\Http\Request;

/**
 * Interface CrudInterfaces
 *
 * You can use for implement crud controller
 * You can add any different method
 * If you not need any method you can throw exception
 *
 * @package App\Interfaces
 */
interface CrudInterface
{
    /**
     * For all records, if you need filter you can use $request->input('get_parameter_name')
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request);

    /**
     *  For create a new record
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function create(Request $request);

    /**
     * For get record by id
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return mixed
     */
    public function show(Request $request, $id);

    /**
     *  For update record by id, you must send id in json body
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function update(Request $request);

    /**
     * For delete record by id
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return mixed
     */
    public function delete(Request $request, $id);
}