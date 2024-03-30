<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class ServersController extends Controller
{
    public function get(int|string $id)
    {
        $servers = [10 => [
            "id" => 22,
            "ext_id" => 10,
            "user_id" => 1,
            "team_id" => 1,
            "plan_id" => 1,
            "promocode" => null,
            "manually_suspended" => 0,
            "last_debited_at" => "2024-03-30 13:21:02",
            "created_at" => "2024-01-21T00:50:03.000000Z",
            "updated_at" => "2024-03-30T10:21:02.000000Z",
            "price" => [
                "day" => 2.7,
                "month" => 81,
                "details" => [
                    "plan" => [
                        "id" => 1,
                        "type" => "service",
                        "ram" => 3.2,
                        "cpu_min" => 0.8,
                        "cpu_max" => 1.7,
                        "disk" => 0.3,
                        "database" => 0.35,
                        "backup" => 0.29,
                        "created_at" => "2023-12-31T19:12:23.000000Z",
                        "updated_at" => null
                    ],
                    "node" => 1,
                    "cpu" => [
                        "count" => 1,
                        "price" => [
                            "day" => 0.8,
                            "month" => 24
                        ],
                        "plan" => 0.8
                    ],
                    "ram" => [
                        "count" => 0.5,
                        "price" => [
                            "day" => 1.6,
                            "month" => 48
                        ]
                    ],
                    "disk" => [
                        "count" => 1,
                        "price" => [
                            "day" => 0.3,
                            "month" => 9
                        ]
                    ],
                    "backup" => [
                        "price" => [
                            "day" => 0.29,
                            "month" => 8.7
                        ]
                    ],
                    "database" => [
                        "price" => [
                            "day" => 0.35,
                            "month" => 10.5
                        ]
                    ],
                    "backups" => [
                        "sum" => [
                            "price" => [
                                "day" => 0,
                                "month" => 0
                            ],
                            "size" => 0
                        ]
                    ],
                    "databases" => [
                        "sum" => [
                            "price" => [
                                "day" => 0,
                                "month" => 0
                            ],
                            "size" => 0
                        ]
                    ]
                ]
            ],
            "untransacted" => [
                "days" => 0,
                "until_delete" => 16
            ]
        ]];
    }

    public function get_wh(int $user = null, int $team = null, int $server = null)
    {
        $data = collect();
        $servers = [10 => [
            "id" => 22,
            "ext_id" => 10,
            "user_id" => 1,
            "team_id" => 1,
            "plan_id" => 1,
            "promocode" => null,
            "manually_suspended" => 0,
            "last_debited_at" => "2024-03-30 13:21:02",
            "created_at" => "2024-01-21T00:50:03.000000Z",
            "updated_at" => "2024-03-30T10:21:02.000000Z",
            "price" => [
                "day" => 2.7,
                "month" => 81,
                "details" => [
                    "plan" => [
                        "id" => 1,
                        "type" => "service",
                        "ram" => 3.2,
                        "cpu_min" => 0.8,
                        "cpu_max" => 1.7,
                        "disk" => 0.3,
                        "database" => 0.35,
                        "backup" => 0.29,
                        "created_at" => "2023-12-31T19:12:23.000000Z",
                        "updated_at" => null
                    ],
                    "node" => 1,
                    "cpu" => [
                        "count" => 1,
                        "price" => [
                            "day" => 0.8,
                            "month" => 24
                        ],
                        "plan" => 0.8
                    ],
                    "ram" => [
                        "count" => 0.5,
                        "price" => [
                            "day" => 1.6,
                            "month" => 48
                        ]
                    ],
                    "disk" => [
                        "count" => 1,
                        "price" => [
                            "day" => 0.3,
                            "month" => 9
                        ]
                    ],
                    "backup" => [
                        "price" => [
                            "day" => 0.29,
                            "month" => 8.7
                        ]
                    ],
                    "database" => [
                        "price" => [
                            "day" => 0.35,
                            "month" => 10.5
                        ]
                    ],
                    "backups" => [
                        "sum" => [
                            "price" => [
                                "day" => 0,
                                "month" => 0
                            ],
                            "size" => 0
                        ]
                    ],
                    "databases" => [
                        "sum" => [
                            "price" => [
                                "day" => 0,
                                "month" => 0
                            ],
                            "size" => 0
                        ]
                    ]
                ]
            ],
            "untransacted" => [
                "days" => 0,
                "until_delete" => 16
            ]
        ]];

        foreach ($servers as $srv) {
            $data->put($srv['ext_id'], $srv);
        }
        return $data;
    }

    public function get_dash(string $active_server = null, string $section = null): Collection
    {
        if ($active_server == 'first') {
            $active_server = $this->get_for(team_id: 1)->first()['uuidShort'] ?? null;
        }
        return collect([
            'active_server_call' => $active_server,
            'section' => $section,
        ]);
    }

    public function get_api(int $id)
    {
        return null;
    }

    // panel servers
    public function get_for(int $user_id = null, int $team_id = null, bool $id_only = false)
    {
        $data = collect();


        $server = [
            "id" => 10,
            "external_id" => null,
            "uuid" => "f14b2aca-453f-4264-aaf1-aa177cb1df2d",
            "uuidShort" => "f14b2aca",
            "node_id" => 1,
            "name" => "dev db",
            "description" => "",
            "status" => null,
            "skip_scripts" => 0,
            "owner_id" => 1,
            "memory" => 512,
            "swap" => 0,
            "disk" => 1024,
            "io" => 500,
            "cpu" => 100,
            "threads" => null,
            "oom_disabled" => 0,
            "allocation_id" => 14,
            "nest_id" => 6,
            "egg_id" => 22,
            "startup" => "{ /usr/sbin/mysqld & } && sleep 5 && mysql -u root",
            "image" => "ghcr.io/parkervcp/yolks:mariadb_10.3",
            "allocation_limit" => 2,
            "database_limit" => 0,
            "backup_limit" => 3,
            "created_at" => "2024-01-04T09:18:49.000000Z",
            "updated_at" => "2024-03-14T09:10:34.000000Z",
            "installed_at" => "2024-01-04 12:20:40"
        ];


        if ($id_only) {
            $data->add($server['id']);
        } else {
            $data->put($server['id'], $server);
        }
        return $data;
    }

    public function get_for_adapter(Request $request)
    {
        $servers = $this->get_for(team_id: 1);
        if ($servers) {
            if ($active_server = $servers->first()) {
                $active_server = $active_server['uuidShort'];
            } else {
                $active_server = null;
            }
        } else {
            $active_server = null;
        }
        if ($active_input = $request->string("active")) $active_server = $active_input;

        return response()->json(["servers" => $servers, "active" => $active_server]);
    }


    public function get_for_wh(int $user_id = null, int $team_id = null, bool $id_only = false, bool $index_ext_id = false)
    {
        $data = collect();
        $server = collect([
            "id" => 22,
            "ext_id" => 10,
            "user_id" => 1,
            "team_id" => 1,
            "plan_id" => 1,
            "promocode" => null,
            "manually_suspended" => 0,
            "last_debited_at" => "2024-03-30 13:21:02",
            "created_at" => "2024-01-21T00:50:03.000000Z",
            "updated_at" => "2024-03-30T10:21:02.000000Z",
            "price" => [
                "day" => 2.7,
                "month" => 81,
                "details" => [
                    "plan" => [
                        "id" => 1,
                        "type" => "service",
                        "ram" => 3.2,
                        "cpu_min" => 0.8,
                        "cpu_max" => 1.7,
                        "disk" => 0.3,
                        "database" => 0.35,
                        "backup" => 0.29,
                        "created_at" => "2023-12-31T19:12:23.000000Z",
                        "updated_at" => null
                    ],
                    "node" => 1,
                    "cpu" => [
                        "count" => 1,
                        "price" => [
                            "day" => 0.8,
                            "month" => 24
                        ],
                        "plan" => 0.8
                    ],
                    "ram" => [
                        "count" => 0.5,
                        "price" => [
                            "day" => 1.6,
                            "month" => 48
                        ]
                    ],
                    "disk" => [
                        "count" => 1,
                        "price" => [
                            "day" => 0.3,
                            "month" => 9
                        ]
                    ],
                    "backup" => [
                        "price" => [
                            "day" => 0.29,
                            "month" => 8.7
                        ]
                    ],
                    "database" => [
                        "price" => [
                            "day" => 0.35,
                            "month" => 10.5
                        ]
                    ],
                    "backups" => [
                        "sum" => [
                            "price" => [
                                "day" => 0,
                                "month" => 0
                            ],
                            "size" => 0
                        ]
                    ],
                    "databases" => [
                        "sum" => [
                            "price" => [
                                "day" => 0,
                                "month" => 0
                            ],
                            "size" => 0
                        ]
                    ]
                ]
            ],
            "untransacted" => [
                "days" => 0,
                "until_delete" => 16
            ]
        ]);
        $data->put(10, $server);
        return $data;
    }

    public function get_for_wh_adapter(Request $request)
    {
        return response()->json($this->get_for_wh(team_id: 1, index_ext_id: true));
    }

    public function get_for_api(int $id)
    {
        return null;
    }

    public function create(string $name,
                           int    $node_id,
                           int    $nest,
                           int    $egg,
                           int    $cpu,
                           int    $ram,
                           int    $disk,
        // int     $user = null,
                           int    $team = null,
        // int     $panel_user = null,
                           int    $swap = 0,
                           int    $io = 500,
                           int    $databases = 0,
                           int    $backups = 3,
                           int    $allocations = 2,
                                  $allocation = null,
                           array  $env = null,
                           string $ver = 'latest',
                           bool   $oom_disabled = false,
                           string $promocode = null,
    )
    {
        return [];
    }

    public function check_auth(int $server_ext_id, bool $collect = false): bool|Collection
    {
        // EDITED TO ALWAYS RETURN PASS / TRUE
        if ($collect) {
            return collect([
                "provider" => "wings_pass",
                "server_ext_id" => $server_ext_id,
                "pass" => true
            ]);
        } else {
            return true;
        }
    }

    public function create_adapter(Request $request)
    {
        $input = $request->input();
        Validator::make($input, [
            'service' => ['required', 'string'],
            'type' => ['required', 'string'],
            'version' => ['required', 'string'],
            'core' => ['required', 'string'],
            'node' => ['required', 'int'],
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'cpu' => ['required', 'numeric', 'min:0.5', 'max:8', 'regex:/^\d+(\.\d)?$/'], // TODO: get max from func
            'ram' => ['required', 'numeric', 'min:0.5', 'max:16', 'regex:/^\d+(\.\d)?$/'], // TODO: get max from func
            'disk' => ['required', 'numeric', 'min:0.5', 'max:50', 'regex:/^\d+(\.\d)?$/'], // TODO: get max from func
            'databases' => ['required', 'boolean'],
            'backups' => ['required', 'boolean'],
            'promocode' => ['nullable', 'string'],
        ])->validate();

        return null;
    }

    public function get_allocations(int|string|array|Collection $servers = null)
    {
        $data = collect();

        $allocation = [
            14 =>
                [
                    "id" => 14,
                    "node_id" => 1,
                    "ip" => "0.0.0.0",
                    "ip_alias" => "93.100.190.142",
                    "port" => 25578,
                    "server_id" => 10,
                    "notes" => null,
                    "created_at" => null,
                    "updated_at" => "2024-01-04T09:18:49.000000Z"
                ]
        ];

        $data->put(10, $allocation);

        return $data;
    }

    public function suspend(int $server_ext_id, bool $suspend = true)
    {
        return null;
    }

    public function delete(int $server_ext_id)
    {
        return null;
    }

    /*
     * BACKEND2FRONTEND FUNCTIONS
     * Used for calls from frontend and can be used in other places as normal backend functions
     * Some functions here uses auth check
     */

    public function manual_suspend(Request $request)
    {
        return null;
    }

    public function switch_team(Request $request)
    {
        return null;
    }

    public function delete_adapter(Request $request)
    {
        return null;
    }

    public function set_primary_allocation(int|string $server_ext_id, int $allocation_id)
    {
        return null;
    }

    public function edit_resources(int|string $server_ext_id, float $target_cpu = null, float $target_ram = null, float $target_disk = null, bool|Collection $target_backups = false, bool|Collection $target_databases = false, Request $req = null)
    {
        return null;
    }

    public function get_subusers(int $server_ext_id)
    {
        return null;
    }
}
