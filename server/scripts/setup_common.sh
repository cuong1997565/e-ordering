#!/bin/bash
if [ ! -f ../.docker/server/nginx.conf ]; then
	cp ../.docker/server/nginx.conf.example ../.docker/server/nginx.conf
fi
if [ ! -f .env ]; then
	cp .env.example .env
fi
