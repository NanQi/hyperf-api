echo "Reloading..."
cmd=$(pidof ynyn)

kill -USR1 "$cmd"
echo "Reloaded"
