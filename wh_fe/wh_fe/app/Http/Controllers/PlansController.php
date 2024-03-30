<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Controllers;

use App\Models\Nodes;
use App\Models\PanelServers;
use App\Models\Plans;
use App\Models\Promocodes;
use App\Models\Servers;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PlansController extends Controller
{
    public function get(int $id)
    {
        return ['id' => 1, 'type' => 'service', 'ram' => 1.7, 'cpu_min' => 0.75, 'cpu_max' => 1.1, 'disk' => 0.3, 'database' => 0.32, 'backup' => 0.29, 'created_at' => '2023-12-31 22:12:23', 'updated_at' => null];
    }

    public function get_latest(string $server_type = 'service', int $node_ext_id = null)
    {
        return ['id' => 1, 'type' => 'service', 'ram' => 1.7, 'cpu_min' => 0.75, 'cpu_max' => 1.1, 'disk' => 0.3, 'database' => 0.32, 'backup' => 0.29, 'created_at' => '2023-12-31 22:12:23', 'updated_at' => null];
    }

    public function get_cpu_price(int $node_ext_id = null, int $plan = null)
    {
        return collect(["node" => $node_ext_id, "price" => 0.75]);
    }

    public function calc(
        string     $server_type = 'service',
        int        $node = null,
        float      $cpu = null,
        float      $ram = null,
        float      $disk = null,
        Collection $backups = null,
        Collection $databases = null,
        string     $promocode = null,
        int        $plan = null,
        array      $input = null,
    )
    {
        $data = collect();
        $details = collect();
        if ($input) {
            $server_type = $input['server_type'] ?? $server_type;
            $node = $input['node'] ?? $node;
            $cpu = $input['cpu'] * 100 ?? $cpu;
            $ram = $input['ram'] * 1024 ?? $ram;
            $disk = $input['disk'] * 1024 ?? $disk;
            $backups = $input['backups'] ?? $backups;
            $promocode = $input['promocode'] ?? $promocode;
            $plan = $input['plan'] ?? $plan;
        }

        if ($plan) {
            if ($plan = $this->get($plan)) {
                if ($plan['type'] != $server_type) return "ERROR: server_type";
            } else return "ERROR: plan not found";
        } else {
            $plan = $this->get_latest($server_type);
        }
        $cpu_price_calc = $this->get_cpu_price(node_ext_id: $node, plan: $plan['id']);
        $plan['ram'] += $cpu_price_calc->get("price");
        $details->put("plan", $plan);

        $cpu_price = round(round($cpu / 100, 2) * $cpu_price_calc->get("price"), 2);
        $ram_price = round(round($ram / 1024, 2) * $plan['ram'], 2);
        $disk_price = round(round($disk / 1024, 2) * $plan['disk'], 2);
        $price_day = ($cpu_price + $ram_price + $disk_price);

        $details->put('node', $cpu_price_calc->get("node"));
        $details->put('cpu', ["count" => round($cpu / 100, 2), "price" => ["day" => $cpu_price, "month" => $cpu_price * 30], "plan" => $this->get_cpu_price($node, $plan['id'])->get('price')]);
        $details->put('ram', ["count" => round($ram / 1024, 2), "price" => ["day" => $ram_price, "month" => $ram_price * 30]]);
        $details->put('disk', ["count" => round($disk / 1024, 2), "price" => ["day" => $disk_price, "month" => $disk_price * 30]]);
        $details->put('backup', ["price" => ["day" => $plan['backup'], "month" => $plan['backup'] * 30]]);
        $details->put('database', ["price" => ["day" => $plan['database'], "month" => $plan['database'] * 30]]);

        $backups_details = collect();
        if ($backups instanceof Collection) {
            $backups_sizes = collect();
            $backups_sizes_sum = 0;
            $backups_price_sum = 0;

            $price_day += $backups_price_sum;
            $backups_details->put("sum", ["price" => ["day" => round($backups_price_sum, 2), "month" => round($backups_price_sum * 30, 2)], "size" => $backups_sizes_sum]);
        }
        $details->put("backups", $backups_details);

        $databases_details = collect();
        if ($databases) {
            $databases_sizes_sum = 0;
            $databases_price_sum = 0;
            $databases_details->put("sum", ["price" => ["day" => $databases_price_sum, "month" => $databases_price_sum * 30], "size" => $databases_sizes_sum]);
        }
        $details->put("databases", $databases_details);

        $price_day = round($price_day, 2);
        $price_month = round($price_day * 30, 2);

        $data->put('day', $price_day);
        $data->put('month', $price_month);
        $data->put('details', $details);

        return $data;
    }

    public function calc_adapter(int|string $server_ext_id, array $input = null)
    {

        $cpu = 100;
        $ram = 1024;
        $disk = 1024;
        $node = 1;
        $plan = 1;
        $backups = collect();
        $databases = collect();
        $promocode = null;

        return $this->calc(node: $node, cpu: $cpu, ram: $ram, disk: $disk, backups: $backups, databases: $databases, promocode: $promocode, plan: $plan);
        // return collect([$cpu, $ram, $disk, $backups, $databases, $promocode, $plan]);
    }

    public function calc_stats(Request $request)
    {
        return null;
    }
}
