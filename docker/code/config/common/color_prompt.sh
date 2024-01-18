NORMAL="\[\e[0m\]"
RED="\[\e[1;31m\]"
GREEN="\[\e[1;32m\]"
USERNAME=$(whoami)

if [ "$USERNAME" = root ]; then
    printf "\e[1;31m \nWarning Running as Root! Please ensure this is desired.\nTo switch to ECS user type ecs-shell and press ENTER.\n\n"
    PS1="$RED$USERNAME [\w]# $NORMAL"
else
    PS1="$GREEN$USERNAME [\w]\$ $NORMAL"
fi
