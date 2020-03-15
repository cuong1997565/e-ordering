#!/bin/bash
if [ ! -f ../.docker/server/nginx.conf ]; then
	cp ../.docker/server/nginx.prod.conf.example ../.docker/server/nginx.conf
fi
if [ ! -f .env ]; then
	cp .env.prod.example .env
fi
