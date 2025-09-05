<?php

namespace PenguinUi\Traits;

use Illuminate\Support\Arr;

trait ButtonColor
{
    public function setDefaultColor($color){
        $array = [
            'primary' => 'whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-2 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark',
            'secondary' => 'whitespace-nowrap rounded-radius bg-secondary border border-secondary px-4 py-2 text-sm font-medium tracking-wide text-on-secondary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-secondary-dark dark:border-secondary-dark dark:text-on-secondary-dark dark:focus-visible:outline-secondary-dark',
            'alternate' => 'whitespace-nowrap rounded-radius bg-surface-alt border border-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface-strong transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt',
            'inverse' => 'whitespace-nowrap rounded-radius bg-surface-dark border border-surface-dark px-4 py-2 text-sm font-medium tracking-wide text-on-surface-dark transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-dark active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface dark:border-surface dark:text-on-surface dark:focus-visible:outline-surface',
            'info' => 'whitespace-nowrap rounded-radius bg-info border border-info px-4 py-2 text-sm font-medium tracking-wide text-onInfo transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-info active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-info dark:border-info dark:text-onInfo dark:focus-visible:outline-info',
            'danger' => 'whitespace-nowrap rounded-radius bg-danger border border-danger px-4 py-2 text-sm font-medium tracking-wide text-onDanger transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-danger active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-danger dark:border-danger dark:text-onDanger dark:focus-visible:outline-danger',
            'warning' => 'whitespace-nowrap rounded-radius bg-warning border border-warning px-4 py-2 text-sm font-medium tracking-wide text-onWarning transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-warning active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-warning dark:border-warning dark:text-onWarning dark:focus-visible:outline-warning',
            'success' => 'whitespace-nowrap rounded-radius bg-success border border-success px-4 py-2 text-sm font-medium tracking-wide text-onSuccess transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-success active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-success dark:border-success dark:text-onSuccess dark:focus-visible:outline-success',
        ];
        if (! Arr::has($array,$color)){
            $color = 'primary';
        }
        return Arr::get($array, $color);
    }
    public function setOutlineColor($color)
    {
        $array = [
            'primary' => 'whitespace-nowrap bg-transparent rounded-radius border border-primary px-4 py-2 text-sm font-medium tracking-wide text-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-primary-dark dark:text-primary-dark dark:focus-visible:outline-primary-dark',
            'secondary' => 'whitespace-nowrap bg-transparent rounded-radius border border-secondary px-4 py-2 text-sm font-medium tracking-wide text-secondary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-secondary-dark dark:text-secondary-dark dark:focus-visible:outline-secondary-dark',
            'alternate' => 'whitespace-nowrap bg-transparent rounded-radius border border-outline px-4 py-2 text-sm font-medium tracking-wide text-outline transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-outline active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-outline-dark dark:text-outline-dark dark:focus-visible:outline-outline-dark',
            'inverse' => 'whitespace-nowrap bg-transparent rounded-radius border border-surface-dark px-4 py-2 text-sm font-medium tracking-wide text-surface-dark transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-dark active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-surface dark:text-surface dark:focus-visible:outline-surface',
            'info' => 'whitespace-nowrap bg-transparent rounded-radius border border-info px-4 py-2 text-sm font-medium tracking-wide text-info transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-info active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-info dark:text-info dark:focus-visible:outline-info',
            'danger' => 'whitespace-nowrap bg-transparent rounded-radius border border-danger px-4 py-2 text-sm font-medium tracking-wide text-danger transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-danger active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-danger dark:text-danger dark:focus-visible:outline-danger',
            'warning' => 'whitespace-nowrap bg-transparent rounded-radius border border-warning px-4 py-2 text-sm font-medium tracking-wide text-warning transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-warning active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-warning dark:text-warning dark:focus-visible:outline-warning',
            'success' => 'whitespace-nowrap bg-transparent rounded-radius border border-success px-4 py-2 text-sm font-medium tracking-wide text-success transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-success active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-success dark:text-success dark:focus-visible:outline-success',
        ];
        if (! Arr::has($array,$color)){
            $color = 'primary';
        }
        return Arr::get($array, $color);
    }


    public function setBadgeClass()
    {
        $array = [
            'default' => 'rounded-radius w-fit border border-outline bg-surface-alt px-2 py-1 text-xs font-medium text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark',
            'inverse' => 'rounded-radius w-fit border border-outline-dark bg-surface-dark-alt px-2 py-1 text-xs font-medium text-on-surface-dark dark:border-outline dark:bg-surface-alt dark:text-on-surface',
            'primary' => 'rounded-radius w-fit border border-primary bg-primary px-2 py-1 text-xs font-medium text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary',
            'secondary' => 'rounded-radius w-fit border border-secondary bg-secondary px-2 py-1 text-xs font-medium text-on-secondary dark:border-secondary-dark dark:bg-secondary-dark dark:text-on-secondary-dark',
            'info' => 'rounded-radius w-fit border border-info bg-info px-2 py-1 text-xs font-medium text-on-info dark:border-info dark:bg-info dark:text-on-info',
            'success' => 'rounded-radius w-fit border border-success bg-success px-2 py-1 text-xs font-medium text-on-success dark:border-success dark:bg-success dark:text-on-success',
            'warning' => 'rounded-radius w-fit border border-warning bg-warning px-2 py-1 text-xs font-medium text-on-warning dark:border-warning dark:bg-warning dark:text-on-warning',
            'danger' => 'rounded-radius w-fit border border-danger bg-danger px-2 py-1 text-xs font-medium text-on-danger dark:border-danger dark:bg-danger dark:text-on-danger',
        ];
        if (! Arr::has($array,$this->attributes->has('badgeColor'))){
            $color = 'primary';
        }else{
            $color = $this->attributes->get('badgeColor');
        }
        return Arr::get($array, $color);
    }
}