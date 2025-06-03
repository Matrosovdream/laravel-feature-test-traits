<?php

namespace Tests\Feature\Traits;


trait EntityTestable {
    protected $createdRecords = [];
    protected $model;

    //protected $user_id;

    protected function createRecord(array $data = [])
    {
        $record = $this->model->create($data);
        if ($record) {
            $this->createdRecords[] = $record;
        }
        return $record;
    }

    protected function updateRecord( $record_id, array $data = [] )
    {
        $record = $this->model->find($record_id);
        $record->update($data);
        return $record->refresh();
    }

    protected function deleteRecord( $record_id ): void
    {
        $record = $this->model->find($record_id);
        $record->delete();
    }

    protected function findRecord(array $filter)
    {
        return $this->model->where($filter)->first();
    }

    protected function deleteAllRecords(): void
    {
        foreach ($this->createdRecords as $record) {
            if( $this->model->find($record->id) ) {
                // Only delete if the record still exists
                $this->deleteRecord($record->id);
            }
        }
        $this->createdRecords = [];
    }

    protected function getRoute(string $key, array $params = []): string
    {
        if (!isset($this->routes[$key])) {
            return '';
        }
        return route($this->routes[$key], $params);
    }

    protected function storeRecordTest( string $route, array $values, $cleanUp = false ): void{

        // Perform the POST request
        $response = $this->actingAs($this->user)->
            post(
                $route,
                $values['new']
        );

        // Verify the record exists
        $record = $this->findRecord( $values['new'] );

        // Assert
        $this->assertNotNull($record, 'Record was not created successfully.');

        if ($record) {
            $this->createdRecords[] = $record;
        }

        // Clean up
        if ($cleanUp) {
            $this->deleteAllRecords();
        }

    }

    protected function updateRecordTest( string $routeCreate, string $routeUpdate, array $values, $cleanUp = false ): void {

        // Create a record to update
        $record = $this->createRecord( $values['new'] );

        // Perform the PUT request
        $response = $this->actingAs($this->user)->
            post(
                route($routeUpdate, $record->id), 
                $values['update']
        );

        // Verify the record was updated
        $updatedRecord = $this->findRecord( $values['update'] );

        // Assert
        $this->assertNotNull($updatedRecord, 'Record was not updated successfully.');

        // Clean up
        if ($cleanUp) {
            $this->deleteAllRecords();
        }

    }   

    protected function deleteRecordTest( string $routeDelete, array $values, $cleanUp = false ): void {

        // Create a record to delete
        $record = $this->createRecord( $values['new'] );

        // Perform the DELETE request
        $response = $this->actingAs($this->user)->
            delete(
                route($routeDelete, $record->id)
        );

        // Verify the record was deleted
        $deletedRecord = $this->findRecord( ['id' => $record->id] );

        // Assert
        $this->assertNull($deletedRecord, 'Record was not deleted successfully.');

        // Clean up
        if ($cleanUp) {
            $this->deleteAllRecords();
        }

    }

}

