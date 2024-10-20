#!/bin/bash
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
GREEN='\033[0;32m'
NC='\033[0m' # No color

# Deinitialize all submodules
echo -e "${MAGENTA}Deinitializing all submodules...${NC}"
git submodule deinit -f --all

# Remove cached submodule directories
echo -e "${MAGENTA}Removing cached submodule directories...${NC}"
rm -rf .git/modules/*

# Initialize and update submodules recursively
echo -e "${MAGENTA}Updating and initializing submodules...${NC}"
git submodule update --init --recursive

# Synchronize submodule configurations
echo -e "${MAGENTA}Synchronizing submodules...${NC}"
git submodule sync --recursive

# Update all submodules to their latest commit from the remote repository
echo -e "${MAGENTA}Updating all submodules to their latest commit...${NC}"
git submodule foreach --recursive "
    echo -e \"${CYAN}Updating submodule in \$name${NC}\";

    # Fetch all updates from the remote repository
    git fetch --all;

    # Determine if we should checkout master or main branch
    branch_name=\$(git branch -r | grep -E 'origin/main|origin/master' | head -n 1 | sed 's#origin/##')

    if [ -z \"\$branch_name\" ]; then
        echo -e \"${RED}No main or master branch found for submodule \$name.${NC}\";
    else
        echo -e \"${CYAN}Checking out branch \$branch_name for submodule \$name.${NC}\";
        git checkout \$branch_name;
        git pull;
    fi
"


echo -e "${GREEN}Submodule update complete!${NC}"
