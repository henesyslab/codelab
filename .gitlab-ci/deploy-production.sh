#!/bin/bash

# Pasta do projeto
cd ~/brain

# Verifica se não houve alterações nos arquivos versionados do sistema
if ! git diff-index --quiet HEAD --; then
  echo "Houve alterações nos arquivos do projeto:" >&2
  git status
  echo "\nPor favor, faça o deploy manualmente." >&2
  exit 1
fi

# Atualiza os arquivos do projeto
git checkout production
git pull --rebase
