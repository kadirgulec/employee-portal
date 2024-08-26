<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Department;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make(__('filament-panels::translations.departments.tabs.all'))->badge($this->getModel()::count())];

        $departments = Department::orderBy('name')
            ->get();


        foreach ($departments as $department) {
            $name = $department->name;
            $slug = str($name)->slug()->toString();

            $tabs[$slug] = Tab::make($name)
                ->label(__('filament-panels::translations.departments.tabs.' . $slug))
                ->badge($department->users()->count()) // Badge showing the number of users in the department
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
