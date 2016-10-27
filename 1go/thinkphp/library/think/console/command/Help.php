<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\console\command;

use think\console\Input;
use think\console\input\Argument as InputArgument;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\console\helper\Descriptor as DescriptorHelper;

class Help extends Command
{

    private $command;

    
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this->setName('help')->setDefinition([
            new InputArgument('command_name', InputArgument::OPTIONAL, 'The command name', 'help'),
            new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command help'),
        ])->setDescription('Displays help for a command')->setHelp(<<<EOF
The <info>%command.name%</info> command displays help for a given command:

  <info>php %command.full_name% list</info>

To display the list of available commands, please use the <info>list</info> command.
EOF
        );
    }

    
    public function setCommand(Command $command)
    {
        $this->command = $command;
    }

    
    protected function execute(Input $input, Output $output)
    {
        if (null === $this->command) {
            $this->command = $this->getConsole()->find($input->getArgument('command_name'));
        }


        $helper = new DescriptorHelper();
        $helper->describe($output, $this->command, [
            'raw_text' => $input->getOption('raw'),
        ]);

        $this->command = null;
    }
}