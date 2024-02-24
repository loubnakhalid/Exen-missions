#!/bin/bash

# Simple Interest Calculator

# Function to calculate simple interest
calculate_simple_interest() {
    principal=$1
    rate=$2
    time=$3
    
    # Calculate the simple interest
    interest=$(echo "scale=2; $principal * $rate * $time / 100" | bc)
    
    # Print the result
    echo "Simple Interest: $interest"
}

# Input principal amount
read -p "Enter the principal amount: " principal

# Input interest rate
read -p "Enter the annual interest rate (in percentage): " rate

# Input time period
read -p "Enter the time period (in years): " time

# Call the function to calculate simple interest
calculate_simple_interest $principal $rate $time
