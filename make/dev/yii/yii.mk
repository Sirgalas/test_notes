yii-init:
	docker-compose run --rm php-cli php init
migration-create:
	docker-compose run --rm php-cli php yii migrate/create $(filter-out $@, $(MAKECMDGOALS)) --interactive=0
migration-up:
	docker-compose run --rm php-cli php yii migrate --interactive=0
migration-down:
	docker-compose run --rm php-cli php yii migrate/down --interactive=0
migration-user:
	docker-compose run --rm php-cli php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations  --interactive=0
console:
	docker-compose run --rm php-cli php yii $(filter-out $@, $(MAKECMDGOALS))
