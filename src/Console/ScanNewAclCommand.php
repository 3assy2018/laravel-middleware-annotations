<?php

namespace M3assy\LaravelAnnotations\Console;

use Exception;
use Illuminate\Console\Command;
use M3assy\LaravelAnnotations\Annotations\Permission;
use M3assy\LaravelAnnotations\Annotations\Role;
use M3assy\LaravelAnnotations\Foundation\AnnotationScanner;
use M3assy\LaravelAnnotations\Foundation\Pipes\AclScanClass;
use M3assy\LaravelAnnotations\Foundation\Pipes\AclScanMethod;
use M3assy\LaravelAnnotations\M3assy\LaravelAnnotations\Foundation\Pipes\ACLCreator;

class ScanNewAclCommand extends Command
{
    private $scanFor = [
        Permission::class,
        Role::class,
    ];

    private $sortedScannerPipes = [
        AclScanClass::class,
        AclScanMethod::class,
        ACLCreator::class,
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:acl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scanning Registered Controller For New ACL to Create';

    protected $scanner;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->scanner = new AnnotationScanner();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        try{
            $this
                ->scanner
                ->addPipe(...$this->sortedScannerPipes)
                ->setScanFor($this->scanFor)
                ->scan()
                ->getResults();
            $this->info("New ACLs Created Successfully");
        }
        catch (Exception $exception){
            $this->error("Something went wrong");
            $this->error($exception->getMessage());
            throw $exception;
        }
    }
}
