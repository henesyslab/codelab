#!/bin/bash

# Pasta do projeto
cd ~/beta_brain

# Verifica se não houve alterações nos arquivos versionados do sistema
if ! git diff-index --quiet HEAD --; then
  echo "Houve alterações nos arquivos do projeto." >&2
  git status
  exit 1
fi

# Atualiza os arquivos do projeto
git checkout master
git pull --rebase
