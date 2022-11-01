<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Refresh migration, continue?')) {
            $this->command->call('migrate:refresh');
            $this->command->warn("Refresh migration done.");
        }

        $role = Role::firstOrCreate(['name' => 'SUPERUSER', 'description' => 'Super User Role', 'guard_name' => 'web']);
        
        $user = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
        ]);

        $this->generatePermissions();

        $role->permissions()->sync(Permission::all());
        $user->roles()->sync(Role::all());

        $this->command->warn('This is your initial account, you can change it later.');

        $this->command->info('Role'. ':' .'SUPERUSER');
        $this->command->info('Email'. ':' .'admin@example.com');
        $this->command->info('Password'. ':' .'password');


        $this->command->warn("Generating default menu...");

        $links = [
            [
                'sort' => 1,
                'parent_id' => null,
                'name' => 'Home',
                'url' => '/home',
                'route' => 'home.index',
            ],
            [
                'sort' => 3,
                'parent_id' => null,
                'name' => 'Permission',
                'url' => '/permission',
                'route' => 'permission.index',
            ],
            [
                'sort' => 4,
                'parent_id' => null,
                'name' => 'Role',
                'url' => '/role',
                'route' => 'role.index',
            ],
            [
                'sort' => 5,
                'parent_id' => null,
                'name' => 'Menu',
                'url' => '/menu',
                'route' => 'menu.index',
            ],
            [
                'sort' => 6,
                'parent_id' => null,
                'name' => 'User',
                'url' => '/user',
                'route' => 'user.index',
            ],
        ];
  
        Menu::insert($links);
    }

    public function generatePermissions($folder=false)
    {
        $this->command->warn("Generating permissions...");

        if($folder){
            $directory = "/Http/Controllers/".$folder;
        }else{
            $directory = "/Http/Controllers";
        }

        if(count(glob(app_path()."$directory/*")) !== 0){

            $permissionGroup = strtolower('/'.str_replace('\\','/',$folder));
            $groupRoute = str_replace('/','.',substr($permissionGroup,1));
            $existsGroup = Permission::where('name',$permissionGroup)->first();
            
            if(!$existsGroup){                    
                Permission::create([
                    'name' => $folder ? $groupRoute.'.*' : '*',
                    'url' => $permissionGroup,
                    'params'=> '',
                    'method'=> 'get',
                    'ctrl_path'=> '',
                    'ctrl_name'=> '',
                    'ctrl_action'=> '',
                    'render_view'=> 'welcome',
                    'type'=>'auth',
                    'guard_name'=>'web',
                    'description' => 'Module Group',
                ]);
            }
        }
    
        $ctrl = [];
        foreach (glob(app_path().$directory.'/*') as $key=>$controller) {

                $controllername = basename($controller, '.php');
                if($folder){
                    $class = 'App\Http\Controllers\\'.$folder.'\\'.$controllername;
                }else{
                    $class = 'App\Http\Controllers\\'.$controllername;
                }
          
                if (strlen($controllername) > 10) {
                    if (class_exists($class)) {
                        
                        $array1 = get_class_methods($class);

                        if ($parent_class = get_parent_class($class)) {
                            $array2 = get_class_methods($parent_class);
                            $array3 = array_diff($array1, $array2);
                        } else {
                            $array2 = [];
                            $array3 = $array1;
                        }

                        $ctrl[$controllername] = $array3;

                    }
                   
                }
        }

        foreach($ctrl as $k=>$row){
            
            $cName = substr($k, 0, -10); 

            if($folder){
                $permissionC = '/'.strtolower(str_replace('\\','/',$folder.'\\'.$cName));
            }else{
                $permissionC = '/'.strtolower(str_replace('\\','/',$cName));
            }
          
            $route = str_replace('/','.',substr($permissionC,1));
            $existsC = Permission::where('name',$permissionC)->first();
            
            if($folder){
                $classes = 'App\Http\Controllers\\'.$folder.'\\'.$cName.'Controller';
            }else{
                $classes = 'App\Http\Controllers\\'.$cName.'Controller';
            }

            // Insert default module permissions
            if(!$existsC){                    
                Permission::create([
                    'name' => $folder ? $route.'.*' : $route,
                    'url' => $permissionC,
                    'params'=> '',
                    'method'=> 'get',
                    'ctrl_path'=> $classes,
                    'ctrl_name'=> $cName,
                    'ctrl_action'=> 'index',
                    'type'=>'auth',
                    'guard_name'=>'web',
                    'description' => 'Default'
                ]);
            }
            
            foreach($row as $r){
                
                if($r!='__construct'){

                    if(preg_match('(index|create|edit|show|history)', $r) === 1) { 
                        $method = 'get';
                    }else if(preg_match('(store|post|generate|clone|replicate|copy)', $r) === 1) { 
                        $method = 'post';
                    }else if(preg_match('(update|put)', $r) === 1) { 
                        $method = 'put';
                    }else if(preg_match('(delete|remove|destroy)', $r) === 1) { 
                        $method = 'delete';
                    }else if(preg_match('(recover|restore|change|patch)', $r) === 1) { 
                        $method = 'patch';
                    }else{
                        $method = 'get';
                    } 

                    if(preg_match('(edit|show|update|delete|destroy|remove|recover|restore|change|put|patch|clone|replicate|copy)', $r) === 1) { 
                        $params = '{id}';
                    }else{
                        $params = '';
                    } 

                  
                    $alias = strtolower($route).'.'.$r;
                 
                    if($folder){
                        $permission = '/'.strtolower(str_replace('\\','/',$folder.'\\'.$cName)).'/'.$r;
                    }else{
                        $permission = '/'.strtolower(str_replace('\\','/',$cName)).'/'.$r;
                    }
                    

                    $exists = Permission::where('name',$permission)->first();

                    if(!$exists){
                        $description = '';
                        if($folder){
                            $refClass=new \ReflectionClass("App\Http\Controllers\\".$folder."\\".$k);
                        }else{
                            $refClass=new \ReflectionClass("App\Http\Controllers\\".$k);
                        }
                      
                        if($refClass->hasMethod($r)){
                            if($folder){
                                $refMethod=new \ReflectionMethod("App\Http\Controllers\\".$folder."\\".$k, $r);
                            }else{
                                $refMethod=new \ReflectionMethod("App\Http\Controllers\\".$k, $r);
                            }
                            $description = $refMethod->getDocComment();
                        }
                        

                        $this->command->info($cName.' - '.$r);

                        Permission::create([
                            'name' => $alias,
                            'url' => $permission,
                            'params'=> $params,
                            'method'=>  $method,
                            'ctrl_path'=> $classes,
                            'ctrl_name'=> $cName,
                            'ctrl_action'=> $r,
                            'type'=>'auth',
                            'guard_name'=>'web',
                            'description' => $description
                        ]);
                    }
                }
            }  
        }
    }
}
