@echo off
goto :main

:handle_operation
    set is_gen=0
    set is_serve=0
    set is_fill=0

    if "%mode%" == "gen" set is_gen=1
    if "%mode%" == "generate" set is_gen=1
    if "%mode%" == "-g" set is_gen=1
    if "%mode%" == "serve" set is_serve=1
    if "%mode%" == "-s" set is_serve=1
    if "%mode%" == "fill" set is_fill=1
    if "%mode%" == "-f" set is_fill=1

    if %is_gen%==1 (
        php app/core/utils/mono_cli/cli.php "gen" %2 %3
    ) else if %is_serve%==1 (
        php -S localhost:3000 public/index.phps
    ) else if %is_fill%==1 (
          php app/core/utils/mono_cli/cli.php "fill" %2 
    ) else (
        echo invalid mono command
    )

    set is_gen=
    set is_fill=
    set is_serve=

goto :eof

:main
    set mode=%1

    call :handle_operation %mode% %2 %3

    set mode=
goto :eof
