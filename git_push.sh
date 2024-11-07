#!/bin/bash
# Скрипт для автоматического коммита и отправки на GitHub

# Проверка состояния репозитория
if [ -n "$(git status --porcelain)" ]; then
	# Если есть изменения, добавляем их, коммитим и пушим
	git add .
	git commit -m "Auto commit: $(date)"
	git push origin main
else
	echo "No changes to commit."
fi
