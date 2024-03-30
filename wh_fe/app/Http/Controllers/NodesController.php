<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Controllers;

use App\Models\Nodes;
use App\Models\PanelAllocations;
use App\Models\PanelServers;
use App\Models\Servers;
use HCGCloud\Pterodactyl\Exceptions\InvaildApiTypeException;
use HCGCloud\Pterodactyl\Pterodactyl;
use Illuminate\Http\Request;
use League\CommonMark\Node\Node;
use function Symfony\Component\Translation\t;

class NodesController extends Controller
{
    public function get(int $node = null)
    {
        $data = collect();

        $nodes = [
            1 =>
                [
                    "id" => 1,
                    "ext_id" => 1,
                    "name" => "max",
                    "ip" => "93.100.190.142",
                    "fqdn" => "max.wingshost.ru",
                    "location" => "rus.spb",
                    "cpu" => "Intel Xeon E5530",
                    "cpu_threads" => 16,
                    "cpu_benchmark" => 2339,
                    "ram" => 41960,
                    "disk" => 624640,
                    "created_at" => null,
                    "updated_at" => null,

                ]
        ];

        foreach ($nodes as $node) {
            $node_resources = $this->get_max_resources($node['ext_id']);
            $data->put($node['ext_id'],
                collect($node)->put('load',
                    round(
                        max($node_resources->get('cpu')['used']['percent'] / $node_resources->get('cpu')['total']['percent'],
                            $node_resources->get('ram')['allocated']['mb'] / $node_resources->get('ram')['total']['mb']) * 100, 0)
                )->put('resources', $node_resources));
        }
        return $data;
    }

    public function get_adapter(Request $request)
    {
        $node = $request->integer("node");
        try {
            $nodes = $this->get($node);
        } catch (\Throwable $e) {
            \Log::error($e);
            $message = __("errors.nodes.retrieve");
            return response()->json(["status" => "error", "message" => $message])->setStatusCode(500);
        }
        return response()->json($this->get($node));
    }

    public function get_max_resources(int $node)
    {
        $data = collect();

        $cpu_threads_max = 16;
        $cpu_percent_max = 16 * 100;
        $cpu_threads_used = 0.5;
        $cpu_percent_used = 50;
        $cpu_free_threads = $cpu_threads_max - $cpu_threads_used;
        $cpu_free_percent = $cpu_percent_max - $cpu_percent_used;

        $ram = 10240;
        $disk = 10240;
        $allocated = ['memory' => 1024, 'disk' => 2048];
        $ram_free = $ram - $allocated['memory'];
        $disk_free = $disk - $allocated['disk'];

        $data->put('cpu', [
            "total" => ["threads" => $cpu_threads_max, "percent" => $cpu_percent_max],
            "free" => ["threads" => $cpu_free_threads, "percent" => $cpu_free_percent],
            "used" => ["threads" => $cpu_threads_used, "percent" => $cpu_percent_used]
        ]);

        $data->put('ram', [
            'total' => ['mb' => $ram, 'gb' => $ram / 1024],
            'free' => ['mb' => $ram_free, 'gb' => $ram_free / 1024],
            'allocated' => ['mb' => $allocated['memory'], 'gb' => $allocated['memory'] / 1024],
        ]);

        $data->put('disk', [
            'total' => ['mb' => $disk, 'gb' => $disk / 1024],
            'free' => ['mb' => $disk_free, 'gb' => $disk_free / 1024],
            'allocated' => ['mb' => $allocated['disk'], 'gb' => $allocated['disk'] / 1024],
        ]);

        return $data;
    }

    public function get_allocation(int $node = null)
    {
        return null;
    }
}
