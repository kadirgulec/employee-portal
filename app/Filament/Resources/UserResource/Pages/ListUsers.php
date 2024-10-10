<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Department;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * dynamically creates department filter tabs
     *
     * @return array|Tab[]
     */
    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make(__('filament-panels::translations.department.tabs.all'))->badge($this->getModel()::count())];

        $locale = App::getLocale();
        $departments = Department::orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))")
            ->get();

        foreach ($departments as $department) {
            $name = $department->name;

            $slug = str($name)->slug()->toString();

            $tabs[$slug] = Tab::make($name)
                ->badge($department->department_users()->withoutTrashed()->count())
                ->modifyQueryUsing(function (Builder $query) use ($department) {
                    // Filter users by their department
                    return $query->whereHas('departments', function ($query) use ($department) {
                        $query->where('departments.id', $department->id);
                    });
                });
        }

        return $tabs;
    }

}
