<!-- #Example:

resource "aws_instance" "payment_server" {
  ami           = "ami-0c55b159cbfafe1f0"
  instance_type = "t2.micro"
  tags = {
    Name = "payment-server"
  }
}

resource "aws_security_group" "payment_sg" {
  name        = "payment-security-group"
  description = "Security group for payment server"
  
  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"] # Allow inbound traffic on port 443 (HTTPS)
  }
  
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1" # Allow all outbound traffic
    cidr_blocks = ["0.0.0.0/0"]
  }
}


#Explanation:
This Terraform code snippet defines an AWS EC2 instance (`payment_server`) 
and a corresponding security group (`payment_sg`) specifically tailored for payment processing.
The EC2 instance is tagged as "payment-server,"
and the security group configuration ensures that inbound traffic is restricted to port 443 (HTTPS) 
for secure payment transactions. -->