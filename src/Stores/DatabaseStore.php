<?php namespace Arcanedev\LaravelSettings\Stores;

use Arcanedev\LaravelSettings\Models\Setting as SettingModel;
use Arcanedev\LaravelSettings\Utilities\Arr;
use Closure;

/**
 * Class     DatabaseStore
 *
 * @package  Arcanedev\LaravelSettings\Stores
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DatabaseStore extends AbstractStore
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The eloquent model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The key column name to query from.
     *
     * @var string
     */
    protected $keyColumn;

    /**
     * The value column name to query from.
     *
     * @var string
     */
    protected $valueColumn;

    /**
     * Any query constraints that should be applied.
     *
     * @var \Closure|null
     */
    protected $queryConstraint;

    /**
     * Any extra columns that should be added to the rows.
     *
     * @var array
     */
    protected $extraColumns = [];

    /* -----------------------------------------------------------------
     |  Post-Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Fire the post options to customize the store.
     *
     * @param  array  $options
     */
    protected function postOptions(array $options)
    {
        $this->model = $this->app->make(
            Arr::get($options, 'model', SettingModel::class)
        );
        $this->setConnection(Arr::get($options, 'connection', null));
        $this->setTable(Arr::get($options, 'table', 'settings'));
        $this->setKeyColumn(Arr::get($options, 'columns.key', 'key'));
        $this->setValueColumn(Arr::get($options, 'columns.value', 'value'));
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the db connection to query from.
     *
     * @param  string  $name
     *
     * @return self
     */
    public function setConnection($name)
    {
        $this->model->setConnection($name);

        return $this;
    }

    /**
     * Set the table to query from.
     *
     * @param  string  $name
     *
     * @return self
     */
    public function setTable($name)
    {
        $this->model->setTable($name);

        return $this;
    }

    /**
     * Set the key column name to query from.
     *
     * @param  string  $name
     *
     * @return self
     */
    public function setKeyColumn($name)
    {
        $this->keyColumn = $name;

        return $this;
    }

    /**
     * Set the value column name to query from.
     *
     * @param  string  $name
     *
     * @return self
     */
    public function setValueColumn($name)
    {
        $this->valueColumn = $name;

        return $this;
    }

    /**
     * Set the query constraint.
     *
     * @param  \Closure  $callback
     *
     * @return self
     */
    public function setConstraint(Closure $callback)
    {
        $this->resetLoaded();

        $this->queryConstraint = $callback;

        return $this;
    }

    /**
     * Set extra columns to be added to the rows.
     *
     * @param  array  $columns
     *
     * @return self
     */
    public function setExtraColumns(array $columns)
    {
        $this->resetLoaded();

        $this->extraColumns = $columns;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Unset a key in the settings data.
     *
     * @param  string  $key
     *
     * @return self
     */
    public function forget($key)
    {
        parent::forget($key);

        // because the database store cannot store empty arrays, remove empty
        // arrays to keep data consistent before and after saving
        $segments = explode('.', $key);
        array_pop($segments);

        while ( ! empty($segments)) {
            $segment = implode('.', $segments);

            // non-empty array - exit out of the loop
            if ($this->get($segment)) break;

            // remove the empty array and move on to the next segment
            $this->forget($segment);
            array_pop($segments);
        }

        return $this;
    }

    /**
     * Read the data from the store.
     *
     * @return array
     */
    protected function read()
    {
        return $this->newQuery()
            ->pluck($this->valueColumn, $this->keyColumn)
            ->toArray();
    }

    /**
     * Write the data into the store.
     *
     * @param  array  $data
     */
    protected function write(array $data)
    {
        $changes = $this->getChanges($data);

        $this->syncUpdated($changes['updated']);
        $this->syncInserted($changes['inserted']);
        $this->syncDeleted($changes['deleted']);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a new query builder instance.
     *
     * @param  $insert  bool
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newQuery($insert = false)
    {
        $query = $this->model->newQuery();

        if ( ! $insert) {
            foreach ($this->extraColumns as $key => $value) {
                $query->where($key, '=', $value);
            }
        }

        if ($this->hasQueryConstraint()) {
            $callback = $this->queryConstraint;
            $callback($query, $insert);
        }

        return $query;
    }

    /**
     * Transforms settings data into an array ready to be inserted into the database.
     * Call array_dot on a multidimensional array before passing it into this method!
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function prepareInsertData(array $data)
    {
        $dbData       = [];
        $extraColumns = $this->extraColumns ? $this->extraColumns : [];

        foreach ($data as $key => $value) {
            $dbData[] = array_merge($extraColumns, [
                $this->keyColumn   => $key,
                $this->valueColumn => $value,
            ]);
        }

        return $dbData;
    }

    /**
     * Check if the query constraint exists.
     *
     * @return bool
     */
    protected function hasQueryConstraint()
    {
        return ! is_null($this->queryConstraint) && is_callable($this->queryConstraint);
    }

    /**
     * Get the changed settings data.
     *
     * @param  array  $data
     *
     * @return array
     */
    private function getChanges(array $data)
    {
        $changes = [
            'inserted' => Arr::dot($data),
            'updated'  => [],
            'deleted'  => [],
        ];

        foreach ($this->newQuery()->pluck($this->keyColumn) as $key) {
            if (Arr::has($changes['inserted'], $key))
                $changes['updated'][$key] = $changes['inserted'][$key];
            else
                $changes['deleted'][] = $key;

            Arr::forget($changes['inserted'], $key);
        }

        return $changes;
    }

    /**
     * Sync the updated records.
     *
     * @param  array  $updated
     */
    private function syncUpdated(array $updated)
    {
        foreach ($updated as $key => $value) {
            $this->newQuery()
                 ->where($this->keyColumn, '=', $key)
                 ->update([$this->valueColumn => $value]);
        }
    }

    /**
     * Sync the inserted records.
     *
     * @param  array  $inserted
     */
    private function syncInserted(array $inserted)
    {
        if ( ! empty($inserted)) {
            $this->newQuery(true)->insert(
                $this->prepareInsertData($inserted)
            );
        }
    }

    /**
     * Sync the deleted records.
     *
     * @param  array  $deleted
     */
    private function syncDeleted(array $deleted)
    {
        if ( ! empty($deleted)) {
            $this->newQuery()->whereIn($this->keyColumn, $deleted)->delete();
        }
    }
}
