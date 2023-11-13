#!/bin/bash

unameOut="$(uname -s)"
case "${unameOut}" in
    Linux*)     machine=Linux; sudocmd=sudo; dockercmd=docker; dockercomposecmd=docker-compose;;
    Darwin*)    machine=Mac; sudocmd=sudo; dockercmd=docker; dockercomposecmd=docker-compose;;
    *)          machine=Windows; sudocmd='';  dockercmd=docker.exe; dockercomposecmd=docker-compose.exe;;
esac

echo
$dockercmd exec avapi_php echo -e ICAgICAgICAgICAgICAgICAgICBfIF9fX18gICAgICAgICAgICAgIF8gICAgCiAgICAgL1wgICAgICAgICAgICAoXykgIF8gXCAgICAgICAgICAgIHwgfCAgIAogICAgLyAgXF9fICAgX19fXyBfIF98IHxfKSB8IF9fXyAgIF9fXyB8IHwgX18KICAgLyAvXCBcIFwgLyAvIF9gIHwgfCAgXyA8IC8gXyBcIC8gXyBcfCB8LyAvCiAgLyBfX19fIFwgViAvIChffCB8IHwgfF8pIHwgKF8pIHwgKF8pIHwgICA8IAogL18vICAgIFxfXF8vIFxfXyxffF98X19fXy8gXF9fXy8gXF9fXy98X3xcX1wKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAtPVtDMGQxbmcgVDNzdF09LSAgICA= | base64 --decode
echo
echo "-------------------------------------------------------------------------------------------------------------"
echo
