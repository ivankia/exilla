#!/usr/bin/env bash
./exilla orderbook:get \
  --pf=7000 --pt=10800 \
  --s=1 --limit=45 \
  --l1=150 --l2=250 --l3=300 \
  --schema=discrete_levels